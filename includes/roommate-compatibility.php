<?php
/**
 * UrbanNest - Roommate Compatibility Calculator
 * 
 * Compares two users' profiles/preferences and returns a score 0-100.
 * Each factor carries a defined weight. Only non-null values are scored.
 */

/**
 * Calculate compatibility between current user and a target profile/preference.
 * 
 * @param array $myProfile    Current user's roommate_profiles row
 * @param array $theirProfile Target user's roommate_profiles row
 * @param array $theirPref    Target user's roommate_preferences row (optional)
 * @return array ['score' => int, 'breakdown' => array]
 */
function calculateCompatibility($myProfile, $theirProfile, $theirPref = null) {
    $totalWeight  = 0;
    $earnedPoints = 0;
    $breakdown    = [];

    // --- FACTOR 1: City / Location (weight: 20) ---
    $w = 20;
    $myCity    = strtolower(trim($myProfile['city'] ?? ''));
    $theirCity = strtolower(trim($theirProfile['city'] ?? ($theirPref['preferred_city'] ?? '')));
    if ($myCity && $theirCity) {
        $totalWeight += $w;
        if ($myCity === $theirCity) {
            $earnedPoints += $w;
            $breakdown['City'] = ['score' => $w, 'max' => $w, 'match' => true];
        } else {
            $breakdown['City'] = ['score' => 0, 'max' => $w, 'match' => false];
        }
    }

    // --- FACTOR 2: Monthly Budget (weight: 20) ---
    $w = 20;
    $myBudget = (int)($myProfile['monthly_budget'] ?? 0);
    if ($theirPref) {
        $theirMin = (int)($theirPref['budget_min'] ?? 0);
        $theirMax = (int)($theirPref['budget_max'] ?? 0);
    } else {
        $theirMin = (int)($theirProfile['monthly_budget'] ?? 0) * 0.8;
        $theirMax = (int)($theirProfile['monthly_budget'] ?? 0) * 1.2;
    }
    if ($myBudget > 0 && $theirMax > 0) {
        $totalWeight += $w;
        if ($myBudget >= $theirMin && $myBudget <= $theirMax) {
            $earnedPoints += $w;
            $breakdown['Budget'] = ['score' => $w, 'max' => $w, 'match' => true];
        } elseif ($theirMax > 0) {
            // Partial credit if within 30% range
            $diff = min(abs($myBudget - $theirMin), abs($myBudget - $theirMax));
            $pct  = max(0, 1 - ($diff / max($theirMax, 1)));
            $pts  = round($pct * $w);
            $earnedPoints += $pts;
            $breakdown['Budget'] = ['score' => $pts, 'max' => $w, 'match' => false];
        }
    }

    // --- FACTOR 3: Food Preference (weight: 15) ---
    $w = 15;
    $myFood    = $myProfile['food_preference'] ?? '';
    $theirFood = $theirProfile['food_preference'] ?? ($theirPref['food_preference'] ?? '');
    if ($myFood && $theirFood) {
        $totalWeight += $w;
        if ($myFood === $theirFood || $myFood === 'Both' || $theirFood === 'Both') {
            $earnedPoints += $w;
            $breakdown['Food'] = ['score' => $w, 'max' => $w, 'match' => true];
        } else {
            $breakdown['Food'] = ['score' => 0, 'max' => $w, 'match' => false];
        }
    }

    // --- FACTOR 4: Smoking (weight: 10) ---
    $w = 10;
    $mySmoking    = $myProfile['smoking'] ?? '';
    $theirSmoking = $theirProfile['smoking'] ?? ($theirPref['smoking'] ?? '');
    if ($mySmoking && $theirSmoking) {
        $totalWeight += $w;
        if ($mySmoking === $theirSmoking) {
            $earnedPoints += $w;
            $breakdown['Smoking'] = ['score' => $w, 'max' => $w, 'match' => true];
        } elseif ($mySmoking === 'Occasionally' || $theirSmoking === 'Occasionally') {
            $earnedPoints += round($w * 0.5);
            $breakdown['Smoking'] = ['score' => round($w * 0.5), 'max' => $w, 'match' => false];
        } else {
            $breakdown['Smoking'] = ['score' => 0, 'max' => $w, 'match' => false];
        }
    }

    // --- FACTOR 5: Drinking (weight: 10) ---
    $w = 10;
    $myDrinking    = $myProfile['drinking'] ?? '';
    $theirDrinking = $theirProfile['drinking'] ?? ($theirPref['drinking'] ?? '');
    if ($myDrinking && $theirDrinking) {
        $totalWeight += $w;
        if ($myDrinking === $theirDrinking) {
            $earnedPoints += $w;
            $breakdown['Drinking'] = ['score' => $w, 'max' => $w, 'match' => true];
        } elseif ($myDrinking === 'Occasionally' || $theirDrinking === 'Occasionally') {
            $earnedPoints += round($w * 0.5);
            $breakdown['Drinking'] = ['score' => round($w * 0.5), 'max' => $w, 'match' => false];
        } else {
            $breakdown['Drinking'] = ['score' => 0, 'max' => $w, 'match' => false];
        }
    }

    // --- FACTOR 6: Sleeping Schedule (weight: 10) ---
    $w = 10;
    $mySleep    = $myProfile['sleeping_schedule'] ?? '';
    $theirSleep = $theirProfile['sleeping_schedule'] ?? '';
    if ($mySleep && $theirSleep) {
        $totalWeight += $w;
        if ($mySleep === $theirSleep || $mySleep === 'Flexible' || $theirSleep === 'Flexible') {
            $earnedPoints += $w;
            $breakdown['Sleep'] = ['score' => $w, 'max' => $w, 'match' => true];
        } else {
            $breakdown['Sleep'] = ['score' => 0, 'max' => $w, 'match' => false];
        }
    }

    // --- FACTOR 7: Cleanliness (weight: 10) ---
    $w = 10;
    $myClean    = $myProfile['cleanliness'] ?? '';
    $theirClean = $theirProfile['cleanliness'] ?? '';
    if ($myClean && $theirClean) {
        $totalWeight += $w;
        if ($myClean === $theirClean) {
            $earnedPoints += $w;
            $breakdown['Cleanliness'] = ['score' => $w, 'max' => $w, 'match' => true];
        } elseif (abs(cleanlinessRank($myClean) - cleanlinessRank($theirClean)) === 1) {
            $earnedPoints += round($w * 0.5);
            $breakdown['Cleanliness'] = ['score' => round($w * 0.5), 'max' => $w, 'match' => false];
        } else {
            $breakdown['Cleanliness'] = ['score' => 0, 'max' => $w, 'match' => false];
        }
    }

    // --- FACTOR 8: Social Lifestyle (weight: 5) ---
    $w = 5;
    $mySocial    = $myProfile['social_lifestyle'] ?? '';
    $theirSocial = $theirProfile['social_lifestyle'] ?? '';
    if ($mySocial && $theirSocial) {
        $totalWeight += $w;
        if ($mySocial === $theirSocial) {
            $earnedPoints += $w;
            $breakdown['Social'] = ['score' => $w, 'max' => $w, 'match' => true];
        } elseif (abs(socialRank($mySocial) - socialRank($theirSocial)) === 1) {
            $earnedPoints += round($w * 0.5);
            $breakdown['Social'] = ['score' => round($w * 0.5), 'max' => $w, 'match' => false];
        } else {
            $breakdown['Social'] = ['score' => 0, 'max' => $w, 'match' => false];
        }
    }

    // --- COMPUTE FINAL SCORE ---
    if ($totalWeight === 0) return ['score' => 0, 'breakdown' => $breakdown];
    $score = min(100, (int)round(($earnedPoints / $totalWeight) * 100));
    
    return ['score' => $score, 'breakdown' => $breakdown];
}

/**
 * Helper: rank cleanliness for partial matching
 */
function cleanlinessRank($val) {
    $map = ['Very Clean' => 3, 'Moderate' => 2, 'Relaxed' => 1];
    return $map[$val] ?? 2;
}

/**
 * Helper: rank social lifestyle for partial matching
 */
function socialRank($val) {
    $map = ['Very Social' => 3, 'Moderate' => 2, 'Introvert' => 1];
    return $map[$val] ?? 2;
}

/**
 * Get a color class based on score
 */
function compatibilityColor($score) {
    if ($score >= 80) return 'success';
    if ($score >= 60) return 'primary';
    if ($score >= 40) return 'warning';
    return 'danger';
}

/**
 * Get label based on score
 */
function compatibilityLabel($score) {
    if ($score >= 80) return 'Excellent Match';
    if ($score >= 60) return 'Good Match';
    if ($score >= 40) return 'Moderate Match';
    return 'Low Match';
}
