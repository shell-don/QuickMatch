<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Formation;
use App\Models\News;
use App\Models\Offer;
use App\Models\Profession;
use App\Models\Region;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserApplication;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);

        // Create Regions
        $regions = [
            ['name' => 'Île-de-France'],
            ['name' => 'Auvergne-Rhône-Alpes'],
            ['name' => 'Nouvelle-Aquitaine'],
            ['name' => 'Occitanie'],
            ['name' => 'Hauts-de-France'],
            ['name' => 'Provence-Alpes-Côte d\'Azur'],
            ['name' => 'Grand Est'],
            ['name' => 'Bretagne'],
            ['name' => 'Pays de la Loire'],
            ['name' => 'Normandie'],
        ];
        foreach ($regions as $region) {
            Region::firstOrCreate($region);
        }

        // Create Skills
        $skills = [
            'JavaScript', 'Python', 'Java', 'PHP', 'SQL', 'Git', 'Docker', 'React', 'Vue.js', 'Laravel',
            'Node.js', 'TypeScript', 'AWS', 'Azure', 'Kubernetes', 'Machine Learning', 'Data Analysis',
            'HTML/CSS', 'Symfony', 'MySQL', 'PostgreSQL', 'MongoDB', 'REST API', 'Agile', 'Scrum',
        ];
        foreach ($skills as $skillName) {
            Skill::firstOrCreate(['name' => $skillName]);
        }

        // Create Companies
        $companies = [
            ['name' => 'TechCorp France', 'industry' => 'Technologie', 'size' => '500+', 'description' => 'Entreprise leader dans les solutions cloud'],
            ['name' => 'DataVision', 'industry' => 'Data Science', 'size' => '201-500', 'description' => 'Start-up spécialisée en analyse de données'],
            ['name' => 'WebAgency Pro', 'industry' => 'Agence Web', 'size' => '51-200', 'description' => 'Agence digitale pleine croissance'],
            ['name' => 'InnovTech', 'industry' => 'Intelligence Artificielle', 'size' => '11-50', 'description' => 'IA et automatisation'],
            ['name' => 'EcoSoft', 'industry' => 'Développement Durable', 'size' => '51-200', 'description' => 'Tech responsable et écologique'],
            ['name' => 'BankDigital', 'industry' => 'Finance', 'size' => '500+', 'description' => 'Transformation digitale bancaire'],
            ['name' => 'HealthTech Plus', 'industry' => 'Santé', 'size' => '201-500', 'description' => 'Innovation médicale'],
            ['name' => 'RetailTech', 'industry' => 'E-commerce', 'size' => '201-500', 'description' => 'Solutions e-commerce'],
        ];
        $createdCompanies = [];
        foreach ($companies as $companyData) {
            $company = Company::firstOrCreate(
                ['name' => $companyData['name']],
                array_merge($companyData, [
                    'region_id' => Region::inRandomOrder()->first()->id,
                    'website' => 'https://'.strtolower(str_replace(' ', '', $companyData['name'])).'.fr',
                ])
            );
            $createdCompanies[] = $company;
        }

        // Create Offers
        $offers = [
            ['title' => 'Stage Développeur Web', 'type' => 'stage', 'description' => 'Développement frontend avec React', 'requirements' => 'Maîtrise de JavaScript et HTML/CSS'],
            ['title' => 'Alternance Développeur Python', 'type' => 'alternance', 'description' => 'Backend et APIs', 'requirements' => 'Python, SQL, bases de données'],
            ['title' => 'Stage Data Analyst', 'type' => 'stage', 'description' => 'Analyse de données et visualisation', 'requirements' => 'Python, Pandas, SQL'],
            ['title' => 'Alternance DevOps', 'type' => 'alternance', 'description' => 'CI/CD et containerisation', 'requirements' => 'Docker, Git, Linux'],
            ['title' => 'Stagefull Stack Developer', 'type' => 'stage', 'description' => 'Full stack Laravel + Vue.js', 'requirements' => 'PHP, MySQL, JavaScript'],
            ['title' => 'Alternance Mobile App', 'type' => 'alternance', 'description' => 'Applications iOS/Android', 'requirements' => 'Swift, Kotlin, React Native'],
            ['title' => 'Stage Cybersécurité', 'type' => 'stage', 'description' => 'Audit et protection', 'requirements' => 'Réseaux, Linux, sécurité'],
            ['title' => 'Alternance Cloud Engineer', 'type' => 'alternance', 'description' => 'AWS/Azure', 'requirements' => 'Cloud, DevOps, scripting'],
        ];
        $createdOffers = [];
        foreach ($offers as $offerData) {
            $company = $createdCompanies[array_rand($createdCompanies)];
            $region = Region::inRandomOrder()->first();
            $offer = Offer::firstOrCreate(
                ['title' => $offerData['title'], 'company_id' => $company->id],
                array_merge($offerData, [
                    'company_id' => $company->id,
                    'region_id' => $region->id,
                    'salary_min' => rand(800, 1500),
                    'salary_max' => rand(1500, 2500),
                    'duration' => '6 mois',
                    'is_remote' => rand(0, 1),
                    'status' => 'active',
                ])
            );
            $offer->skills()->attach(Skill::inRandomOrder()->take(rand(2, 5))->get());
            $createdOffers[] = $offer;
        }

        // Create Professions
        $professions = [
            ['name' => 'Développeur Web', 'rome_code' => 'M1805', 'description' => 'Création de sites et applications web'],
            ['name' => 'Data Analyst', 'rome_code' => 'M1803', 'description' => 'Analyse et visualisation de données'],
            ['name' => 'DevOps Engineer', 'rome_code' => 'M1804', 'description' => 'Infrastructure et automatisation'],
            ['name' => 'Chef de Projet IT', 'rome_code' => 'M1801', 'description' => 'Gestion de projets digitaux'],
            ['name' => 'UX Designer', 'rome_code' => 'E1205', 'description' => 'Expérience utilisateur'],
        ];
        foreach ($professions as $professionData) {
            $profession = Profession::firstOrCreate(
                ['name' => $professionData['name']],
                $professionData
            );
            $profession->skills()->attach(Skill::inRandomOrder()->take(rand(3, 6))->get());
        }

        // Create Formations
        $formations = [
            ['title' => 'Bachelor Développeur Web', 'type' => 'initial', 'level' => 'Bac+3', 'school' => 'École Web', 'city' => 'Paris', 'duration' => '3 ans'],
            ['title' => 'Master Data Science', 'type' => 'alternance', 'level' => 'Bac+5', 'school' => 'Université Paris Saclay', 'city' => 'Paris', 'duration' => '2 ans'],
            ['title' => 'Bachelor Cybersécurité', 'type' => 'initial', 'level' => 'Bac+3', 'school' => 'École InfoSec', 'city' => 'Lyon', 'duration' => '3 ans'],
            ['title' => 'Master Management Digital', 'type' => 'alternance', 'level' => 'Bac+5', 'school' => 'Kedge BS', 'city' => 'Bordeaux', 'duration' => '2 ans'],
        ];
        foreach ($formations as $formationData) {
            Formation::firstOrCreate(
                ['title' => $formationData['title']],
                array_merge($formationData, [
                    'region_id' => Region::inRandomOrder()->first()->id,
                ])
            );
        }

        // Create News
        $newsItems = [
            ['title' => 'Le marché du stage IT explode en 2026', 'summary' => 'Les entreprises tech recrutent massivement les étudiants en informatique.'],
            ['title' => ' nouvelles aides pour les alternance', 'summary' => 'Le gouvernement annonce des mesures pour favoriser l\'alternance.'],
            ['title' => 'Top 10 compétences recherchées', 'summary' => 'JavaScript, Python et Cloud sont les compétences les plus demandées.'],
            ['title' => 'Salaires développeurs 2026', 'summary' => 'Les rémunérations continuent d\'augmenter dans le secteur tech.'],
        ];
        foreach ($newsItems as $newsData) {
            News::firstOrCreate(
                ['title' => $newsData['title']],
                array_merge($newsData, [
                    'source' => 'SmartIntern',
                    'category' => 'Actualité',
                    'published_at' => now()->subDays(rand(1, 30)),
                ])
            );
        }

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@smartintern.fr'],
            [
                'name' => 'Admin SmartIntern',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole('admin');

        // Create Manager Users (Entreprises)
        $managerNames = ['Marie Dupont', 'Jean Martin', 'Sophie Bernard'];
        foreach ($managerNames as $managerName) {
            $manager = User::firstOrCreate(
                ['email' => strtolower(str_replace(' ', '.', $managerName)).'@entreprise.fr'],
                [
                    'name' => $managerName,
                    'password' => bcrypt('password'),
                    'is_company' => true,
                    'company_id' => $createdCompanies[array_rand($createdCompanies)]->id,
                ]
            );
            $manager->assignRole('manager');
        }

        // Create Student Users with Applications
        $studentNames = [
            'Thomas Petit', 'Lucas Bernard', 'Emma Moreau', 'Chloé Durand',
            'Alexis Rousseau', 'Sarah Lefebvre', 'Mathéo Weber', 'Léa Michel',
            'Hugo Garner', 'Manon Fontaine',
        ];

        foreach ($studentNames as $index => $studentName) {
            $student = User::firstOrCreate(
                ['email' => strtolower(str_replace(' ', '.', $studentName)).'@student.fr'],
                [
                    'name' => $studentName,
                    'password' => bcrypt('password'),
                    'region_id' => Region::inRandomOrder()->first()->id,
                    'bio' => 'Étudiant en informatique passionné par les nouvelles technologies.',
                ]
            );
            $student->assignRole('user');
            $student->skills()->attach(Skill::inRandomOrder()->take(rand(3, 6))->get());

            // Create random applications for each student
            $numApplications = rand(2, 6);
            $shuffledOffers = collect($createdOffers)->shuffle()->take($numApplications);

            foreach ($shuffledOffers as $offer) {
                $statuses = ['pending', 'pending', 'pending', 'accepted', 'rejected'];
                UserApplication::firstOrCreate(
                    [
                        'user_id' => $student->id,
                        'offer_id' => $offer->id,
                    ],
                    [
                        'status' => $statuses[array_rand($statuses)],
                        'applied_at' => now()->subDays(rand(1, 60)),
                    ]
                );
            }
        }

        // Create Test User (for login testing)
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'region_id' => Region::inRandomOrder()->first()->id,
            ]
        );
        $testUser->assignRole('user');
    }
}
