<?php

namespace App\Services;

use App\Models\FoundItem;
use App\Models\ItemMatch;
use App\Models\LostItem;
use Carbon\Carbon;

class MatchingService
{
    private const MATCH_THRESHOLD = 40;

    public function findMatchesForFoundItem(FoundItem $foundItem): void
    {
        $lostItems = LostItem::where('status', 'unclaimed')->get();

        foreach ($lostItems as $lostItem) {
            $score = $this->calculateMatchScore($lostItem, $foundItem);

            if ($score >= self::MATCH_THRESHOLD) {
                ItemMatch::updateOrCreate(
                    [
                        'lost_item_id' => $lostItem->id,
                        'found_item_id' => $foundItem->id,
                    ],
                    [
                        'match_score' => $score,
                        'is_reviewed' => false,
                    ]
                );
            }
        }

        $lostItem->update(['status' => 'matched']);
    }

    public function findMatchesForLostItem(LostItem $lostItem): void
    {
        $foundItems = FoundItem::where('status', 'unclaimed')->get();

        foreach ($foundItems as $foundItem) {
            $score = $this->calculateMatchScore($lostItem, $foundItem);

            if ($score >= self::MATCH_THRESHOLD) {
                ItemMatch::updateOrCreate(
                    [
                        'lost_item_id' => $lostItem->id,
                        'found_item_id' => $foundItem->id,
                    ],
                    [
                        'match_score' => $score,
                        'is_reviewed' => false,
                    ]
                );
            }
        }
    }

    public function calculateMatchScore(LostItem $lostItem, FoundItem $foundItem): float
    {
        $score = 0;

        if (strtolower($lostItem->category) === strtolower($foundItem->category)) {
            $score += 30;
        }

        $nameScore = $this->calculateKeywordScore(
            $lostItem->item_name,
            $foundItem->item_name
        );
        $score += $nameScore * 20;

        $descScore = $this->calculateDescriptionScore(
            $lostItem->description,
            $foundItem->description
        );
        $score += $descScore * 30;

        $locationScore = $this->calculateKeywordScore(
            $lostItem->location,
            $foundItem->location
        );
        $score += $locationScore * 10;

        $dateScore = $this->calculateDateScore($lostItem->date_lost, $foundItem->date_found);
        $score += $dateScore * 10;

        return min($score, 100);
    }

    private function calculateKeywordScore(string $text1, string $text2): float
    {
        $words1 = $this->extractKeywords($text1);
        $words2 = $this->extractKeywords($text2);

        if (empty($words1) || empty($words2)) {
            return 0;
        }

        $common = array_intersect($words1, $words2);
        $total = count(array_unique(array_merge($words1, $words2)));

        return ($total > 0) ? (count($common) / $total) * 100 : 0;
    }

    private function extractKeywords(string $text): array
    {
        $text = strtolower($text);
        $words = preg_split('/\s+/', $text);
        
        $stopWords = ['the', 'a', 'an', 'is', 'are', 'was', 'were', 'it', 'this', 'that', 'with', 'for', 'on', 'in', 'to'];
        
        return array_filter($words, function($word) use ($stopWords) {
            return strlen($word) > 2 && !in_array($word, $stopWords);
        });
    }

    private function calculateDescriptionScore(string $desc1, string $desc2): float
    {
        $words1 = $this->extractKeywords($desc1);
        $words2 = $this->extractKeywords($desc2);

        if (empty($words1) || empty($words2)) {
            return 0;
        }

        $common = array_intersect($words1, $words2);
        $maxCommon = max(count($words1), count($words2));

        return ($maxCommon > 0) ? (count($common) / $maxCommon) * 100 : 0;
    }

    private function calculateDateScore($dateLost, $dateFound): float
    {
        if (!$dateLost || !$dateFound) {
            return 0;
        }

        $lost = Carbon::parse($dateLost);
        $found = Carbon::parse($dateFound);

        $diff = abs($lost->diffInDays($found));

        if ($diff <= 7) {
            return 100;
        } elseif ($diff <= 14) {
            return 50;
        } elseif ($diff <= 30) {
            return 25;
        }

        return 0;
    }
}
