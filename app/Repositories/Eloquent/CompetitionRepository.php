<?php

namespace App\Repositories\Eloquent;

use App\Models\Competition;
use App\Repositories\Contracts\CompetitionRepository as CompetitionContract;
use Morilog\Jalali\Jalalian;

class CompetitionRepository extends BaseRepository implements CompetitionContract
{
    public function __construct(Competition $competition)
    {
        $this->model = $competition;
    }

    public function retrieveByName($competition)
    {
        return $this->model->whereName($competition)->first();
    }

    public function lastWeek($competition, $season)
    {
        return $competition->fixtures()->whereSeason($season)->latest('datetime')->first();
    }

    public function clubs($competition, $season)
    {
        return $competition->standing()->whereSeason($season)->get();
    }

    public function retrieveFixtures($competition, $season, $filter, $currentWeek)
    {
        return $competition->whereSeason($season)->orderBy('datetime')->when($filter, function ($q) use ($filter) {
            if ($filter['type'] == 'period') {
                $q->wherePeriod($filter['value']);
            }
            if ($filter['type'] == 'club') {
                $q->whereHost($filter['value']);
                $q->orWhereGuest($filter['value']);
            }
        })->when(!$filter, function ($q) use ($currentWeek) {
            $q->wherePeriod($currentWeek);
        })->get()->map(fn ($fixture) => [
            'host'       => $fixture->host,
            'hostPoint'  => $fixture->final ? $fixture->host_point : '-',
            'guest'      => $fixture->guest,
            'guestPoint' => $fixture->final ? $fixture->guest_point : '-',
            'datetime'   => Jalalian::forge($fixture->datetime)->format('l ، j F o - ساعت: H:i'),
        ]);
    }

    public function retrieveStanding($competition, $season)
    {
        return $competition->standing()
                           ->whereSeason($season)
                           ->when(in_array($this->model->name, $this->isGrouped()), function ($q) {
                               $q->orderBy('group');
                           })
                           ->orderBy('position')
                           ->get();
    }

    public function retrieveScorers($competition, $season)
    {
        return $competition->scorers()->whereSeason($season)->latest('count_scores')->get();
    }

    private function isGrouped(): array
    {
        return [
            'uefa_champions_league',
            'europe_league',
            'afc_champions_league',
            'europe_nations_league',
            'uefa_euro',
            'afc_asian_cup',
            'world_cup',
        ];
    }
}
