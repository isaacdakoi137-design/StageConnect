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
        foreach (['Admin', 'Entreprise', 'Etudiant'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

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

        // Create Student profile with test data
        Student::firstOrCreate(
            ['user_id' => $student->id],
            [
                'phone' => '+33612345678',
                'birth_date' => '2003-05-15',
                'city' => 'Paris',
                'school' => 'Université Paris-Dauphine',
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

        // Create test offers for the company
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

        // Create application for the student
        Application::firstOrCreate(
            ['user_id' => $student->id, 'offer_id' => $offer1->id],
            [
                'status' => 'En attente',
                'message' => 'Je suis très intéressé par cette position.',
            ]
        );
    }
}
