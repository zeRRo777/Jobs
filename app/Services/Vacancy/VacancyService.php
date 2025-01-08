<?php

namespace App\Services\Vacancy;

use App\Models\Vacancy;
use App\Models\City;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class VacancyService
{
    public function updateVacancy(Vacancy $vacancy, array $data)
    {
        try{
            DB::beginTransaction();
            if (!empty($data['new_city'])) {
                $city = $this->createCity($data['new_city']);
                $data['city_id'] = $city->id;
            }

            if (!empty($data['new_tags'])) {
                $newTagIds = $this->createTags($data['new_tags']);
                $data['tags'] = array_merge($data['tags'] ?? [], $newTagIds);
            }

            $data['tags'] = $data['tags'] ?? [];

            $vacancy->tags()->sync($data['tags']);

            unset($data['new_tags'], $data['tags'], $data['new_city'], $data['company_id'], $data['vacancy_id']);

            $vacancy->update($data);

            DB::commit();

            return $vacancy;
        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    protected function createCity(string $cityName): City
    {
        $city = new City();
        $city->name = $cityName;
        $city->save();
        return $city;
    }

    protected function createTags(string $tagsInput): array
    {
        $tags = explode(',', $tagsInput);
        $tags = array_map('trim', $tags);
        $tagIds = [];
        foreach ($tags as $tagName) {
            $tag = new Tag();
            $tag->name = $tagName;
            $tag->save();
            $tagIds[] = $tag->id;
        }
        return $tagIds;
    }

    public function delete(Vacancy $vacancy)
    {
        try{
            DB::beginTransaction();
            $vacancy->delete();
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function createVacancy(array $data)
    {
        try {
            DB::beginTransaction();

            if (!empty($data['new_city'])) {
                $city = $this->createCity($data['new_city']);
                $data['city_id'] = $city->id;
            }

            if (!empty($data['new_tags'])) {
                $newTagIds = $this->createTags($data['new_tags']);
                $data['tags'] = array_merge($data['tags'] ?? [], $newTagIds);
            }

            $tags = $data['tags'] ?? [];

            unset($data['new_tags'], $data['tags'], $data['new_city']);

            $vacancy = Vacancy::create($data);

            $vacancy->tags()->attach($tags);

            DB::commit();

            return $vacancy;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

