<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Company;
use App\Models\Tag;
use App\Models\User;
use App\Models\Vacancy;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //10 городов
        $cities = City::factory()->count(10)->create();
        //10 тегов
        $tags = Tag::factory()->count(10)->create();
        //10 компаний
        $companies = Company::factory()->count(10)->create();

        //коллекция для вакансий
        $allVacancies = collect();

        //для каждой компании 10 вакансий и 1 админ
        foreach ($companies as $company) {

            //1 случайный город
            $city = $cities->random(1)->first();

            //1 админ
            $user = User::factory()->state(['company_id' => $company->id])->create();

            //добавляем админа к компании
            $user->cities()->attach($city);

            //добавляем город к компании
            $company->cities()->attach($city);

            //10 вакансий
            $vacancies = Vacancy::factory()->count(10)->state(['company_id' => $company->id, 'city_id' => $city->id])->create();

            //добавляем созданные вакансии в коллекцию
            $allVacancies = $allVacancies->merge($vacancies);

            //к каждой вакансии 2 тега и добавление города
            foreach ($vacancies as $vacancy) {
                $vacancy->tags()->attach($tags->random(2));
            }
        }

        //10 обычных пользователей
        $users = User::factory()->count(10)->create();

        //добавление каждому пользователю случайного города
        foreach ($users as $user) {
            $user->cities()->attach($cities->random(1));
        }

        //каждому пользователю 3 случайные понравившиеся вакансии
        foreach ($users as $user) {
            $user->likedVacancies()->attach($allVacancies->random(3));
        }
    }
}
