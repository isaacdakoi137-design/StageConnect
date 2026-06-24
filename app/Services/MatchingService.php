<?php

namespace App\Services;

class MatchingService
{
    /**
     * Calculate compatibility percentage between student skills and offer required skills
     * 
     * @param string|null $studentSkills Semicolon or comma separated skills
     * @param string|null $offerSkills Semicolon or comma separated required skills
     * @return int Percentage (0-100)
     */
    public static function calculateMatchPercentage($studentSkills, $offerSkills)
    {
        // Handle null or empty values
        if (empty($studentSkills) || empty($offerSkills)) {
            return 0;
        }

        // Parse skills - handle both semicolon and comma separators
        $studentSkillsArray = self::parseSkills($studentSkills);
        $offerSkillsArray = self::parseSkills($offerSkills);

        // If either array is empty, return 0
        if (empty($studentSkillsArray) || empty($offerSkillsArray)) {
            return 0;
        }

        // Count matching skills (case-insensitive)
        $matches = 0;
        foreach ($offerSkillsArray as $requiredSkill) {
            foreach ($studentSkillsArray as $studentSkill) {
                if (strtolower(trim($requiredSkill)) === strtolower(trim($studentSkill))) {
                    $matches++;
                    break;
                }
            }
        }

        // Calculate percentage
        $percentage = (int) round(($matches / count($offerSkillsArray)) * 100);

        return min($percentage, 100); // Ensure max is 100%
    }

    /**
     * Parse skills string into array
     * Handles both semicolon and comma separators
     * 
     * @param string $skillsString
     * @return array
     */
    private static function parseSkills($skillsString)
    {
        // Detect separator (semicolon or comma)
        if (strpos($skillsString, ';') !== false) {
            $skills = explode(';', $skillsString);
        } else {
            $skills = explode(',', $skillsString);
        }

        // Filter empty values and trim
        $skills = array_filter(array_map('trim', $skills), function ($skill) {
            return !empty($skill);
        });

        return array_values($skills);
    }

    /**
     * Get matching skills between student and offer
     * 
     * @param string|null $studentSkills
     * @param string|null $offerSkills
     * @return array
     */
    public static function getMatchingSkills($studentSkills, $offerSkills)
    {
        if (empty($studentSkills) || empty($offerSkills)) {
            return [];
        }

        $studentSkillsArray = self::parseSkills($studentSkills);
        $offerSkillsArray = self::parseSkills($offerSkills);

        $matchingSkills = [];
        foreach ($offerSkillsArray as $requiredSkill) {
            foreach ($studentSkillsArray as $studentSkill) {
                if (strtolower(trim($requiredSkill)) === strtolower(trim($studentSkill))) {
                    $matchingSkills[] = trim($studentSkill);
                    break;
                }
            }
        }

        return $matchingSkills;
    }
}
