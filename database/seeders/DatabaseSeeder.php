<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Offer;
use App\Models\Application;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        foreach (['Admin', 'Entreprise', 'Etudiant', 'Ecole', 'Encadreur'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // 2. Seed Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@stageconnect.test'],
            [
                'name' => 'Administrateur StageConnect',
                'password' => Hash::make('password'),
            ]
        );
        $admin->syncRoles(['Admin']);

        $company = User::firstOrCreate(
            ['email' => 'entreprise@stageconnect.test'],
            [
                'name' => 'Entreprise Demo',
                'password' => Hash::make('password'),
            ]
        );
        $company->syncRoles(['Entreprise']);

        $student = User::firstOrCreate(
            ['email' => 'etudiant@stageconnect.test'],
            [
                'name' => 'Etudiant Demo',
                'password' => Hash::make('password'),
            ]
        );
        $student->syncRoles(['Etudiant']);

        $school = User::firstOrCreate(
            ['email' => 'ecole@stageconnect.test'],
            [
                'name' => 'Université de Paris-Saclay',
                'password' => Hash::make('password'),
            ]
        );
        $school->syncRoles(['Ecole']);

        $supervisor = User::firstOrCreate(
            ['email' => 'encadreur@stageconnect.test'],
            [
                'name' => 'Jean Dupont (Encadreur)',
                'password' => Hash::make('password'),
            ]
        );
        $supervisor->syncRoles(['Encadreur']);

        // 3. Create Student profile with test data
        $studentProfile = Student::firstOrCreate(
            ['user_id' => $student->id],
            [
                'phone' => '+33612345678',
                'birth_date' => '2003-05-15',
                'city' => 'Paris',
                'school' => 'Université de Paris-Saclay',
                'level' => 'Bac+3',
                'bio' => 'Étudiant passionné par le développement web et les nouvelles technologies.',
                'skills' => 'PHP; Laravel; Vue.js; JavaScript; MySQL; Git',
                'projects' => 'Portfolio web personnel; Application de gestion de tâches; Système de blog',
                'experiences' => 'Stage développeur frontend chez TechStartup (2 mois); Projet freelance e-commerce',
                'certifications' => 'Certificat Laravel avancé; Certification JavaScript ES6; Formation Git Udemy',
                'cv' => null,
                'photo' => null,
            ]
        );

        // 4. Create test offers for the company
        $offer1 = Offer::firstOrCreate(
            ['title' => 'Développeur Backend PHP/Laravel', 'company' => 'Entreprise Demo', 'user_id' => $company->id],
            [
                'location' => 'Paris',
                'description' => 'Rejoignez notre équipe de développement pour travailler sur nos projets innovants.',
                'deadline' => now()->addMonths(2),
                'domain' => 'Informatique',
                'education_level' => 'Bac+3',
                'salary' => 2250,
                'duration' => '6 mois',
                'contract_type' => 'Stage',
                'work_mode' => 'Hybride',
                'required_skills' => 'PHP; Laravel; MySQL; Git',
            ]
        );

        $offer2 = Offer::firstOrCreate(
            ['title' => 'Développeur Frontend Vue.js', 'company' => 'Entreprise Demo', 'user_id' => $company->id],
            [
                'location' => 'Paris',
                'description' => 'Nous cherchons un développeur frontend créatif pour nos projets clients.',
                'deadline' => now()->addMonths(3),
                'domain' => 'Informatique',
                'education_level' => 'Bac+2',
                'salary' => 2000,
                'duration' => '4 mois',
                'contract_type' => 'Stage',
                'work_mode' => 'Sur site',
                'required_skills' => 'Vue.js; JavaScript; CSS; Git',
            ]
        );

        // 5. Create application for the student
        Application::firstOrCreate(
            ['user_id' => $student->id, 'offer_id' => $offer1->id],
            [
                'status' => 'En attente',
                'message' => 'Je suis très intéressé par cette position de développeur Laravel.',
            ]
        );

        // 6. Seed Badges
        $badge1 = \App\Models\Badge::firstOrCreate(
            ['name' => '🏆 Premier stage'],
            [
                'icon' => 'trophy',
                'description' => 'Déclaré et validé son premier stage sur StageConnect.',
                'trigger_type' => 'first_stage'
            ]
        );

        $badge2 = \App\Models\Badge::firstOrCreate(
            ['name' => '🏆 Profil complet'],
            [
                'icon' => 'user-check',
                'description' => 'Complété l\'intégralité des informations de profil étudiant.',
                'trigger_type' => 'profile_complete'
            ]
        );

        $badge3 = \App\Models\Badge::firstOrCreate(
            ['name' => '🏆 10 candidatures'],
            [
                'icon' => 'send',
                'description' => 'Postulé à 10 offres ou plus sur la plateforme.',
                'trigger_type' => 'applications_count'
            ]
        );

        $badge4 = \App\Models\Badge::firstOrCreate(
            ['name' => '🏆 Développeur Laravel confirmé'],
            [
                'icon' => 'code',
                'description' => 'Réussi le quiz technique Laravel avec un score de 80% ou plus.',
                'trigger_type' => 'developer_laravel'
            ]
        );

        // Award "Profil complet" badge to student
        $student->badges()->syncWithoutDetaching([$badge2->id]);

        // 7. Seed Quizzes
        $quiz = \App\Models\Quiz::firstOrCreate(
            ['title' => 'Test PHP/Laravel Fondamentaux', 'user_id' => $company->id],
            [
                'domain' => 'PHP/Laravel',
                'description' => 'Test technique officiel pour évaluer les bases du framework Laravel et de PHP.'
            ]
        );

        // Questions for the quiz
        \App\Models\QuizQuestion::firstOrCreate(
            ['quiz_id' => $quiz->id, 'question_text' => 'Quel design pattern Laravel utilise-t-il principalement pour l\'injection de dépendances ?'],
            [
                'options' => ['Factory', 'Facade', 'Service Container', 'Observer'],
                'correct_option' => 2,
                'is_code_exercise' => false
            ]
        );

        \App\Models\QuizQuestion::firstOrCreate(
            ['quiz_id' => $quiz->id, 'question_text' => 'Quelle méthode Artisan permet de créer une base de données et d\'exécuter les tables ?'],
            [
                'options' => ['db:seed', 'migrate', 'make:model', 'serve'],
                'correct_option' => 1,
                'is_code_exercise' => false
            ]
        );

        \App\Models\QuizQuestion::firstOrCreate(
            ['quiz_id' => $quiz->id, 'question_text' => 'Écrire une fonction PHP `findMax($arr)` qui prend un tableau d\'entiers en paramètre et retourne la valeur maximale. (Exemple: findMax([1, 5, 3]) retourne 5)'],
            [
                'is_code_exercise' => true,
                'code_starter' => "function findMax(\$arr) {\n    // Écrivez votre code ici\n    return max(\$arr);\n}",
                'code_test_cases' => [
                    ['input' => '[1, 5, 3]', 'output' => '5'],
                    ['input' => '[-10, 0, -2]', 'output' => '0']
                ]
            ]
        );

        // 8. Seed Social Network Posts
        \App\Models\Post::firstOrCreate(
            ['content' => 'Ravi de rejoindre la plateforme StageConnect ! Hâte de trouver mon prochain stage en développement Laravel 🚀', 'user_id' => $student->id],
            ['likes_count' => 5]
        );

        \App\Models\Post::firstOrCreate(
            ['content' => 'Nous venons de publier une offre de stage de 6 mois pour un Développeur Backend PHP/Laravel. N\'hésitez pas à postuler ou partager !', 'user_id' => $company->id],
            ['likes_count' => 12]
        );
    }
}
