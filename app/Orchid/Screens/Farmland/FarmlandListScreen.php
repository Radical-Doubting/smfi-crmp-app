<?php

namespace App\Orchid\Screens\Farmland;

use App\Actions\Farmland\DeleteFarmland;
use App\Models\Farmland\Farmland;
use App\Orchid\Layouts\Farmland\FarmlandListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class FarmlandListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Farmlands';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'List of all farmland under SM KSK SAP';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'farmlands' => Farmland::filters()
                ->defaultSort('id')
                ->paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make(__('Add'))
                ->icon('plus')
                ->route('platform.farmlands.create'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            FarmlandListLayout::class,
        ];
    }

    /**
     * Remove a farmland.
     *
     * @param Farmland $farmland
     * @throws \Exception
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Farmland $farmland)
    {
        return DeleteFarmland::runOrchidAction($farmland, null);
    }
}
