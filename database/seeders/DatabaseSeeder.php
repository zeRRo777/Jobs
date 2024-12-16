<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Company;
use App\Models\Tag;
use App\Models\User;
use App\Models\Vacancy;
use Database\Factories\CityFactory;
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
            //1 админ
            User::factory()->count(1)->admin()->state(['company_id' => $company->id])->create();

            //10 вакансий
            $vacancies = Vacancy::factory()->count(10)->state(['company_id' => $company->id, 'city_id' => $cities->random(1)->first()->id])->create();

            //добавляем созданные вакансии в коллекцию
            $allVacancies = $allVacancies->merge($vacancies);

            //к каждой вакансии 2 тега и добавление города
            foreach ($vacancies as $vacancy) {
                $randomtags = $tags->random(2);
                $vacancy->tags()->attach($randomtags);
            }
        }

        //10 обычных пользователей
        $users = User::factory()->count(10)->create();

        //каждому пользователю 3 случайные понравившиеся вакансии
        foreach ($users as $user) {
            $randomVacancies = $allVacancies->random(3);
            $user->likedVacancies()->attach($randomVacancies);
        }
    }
}
