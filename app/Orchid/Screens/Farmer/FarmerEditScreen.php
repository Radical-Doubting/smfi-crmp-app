<?php

namespace App\Orchid\Screens\Farmer;

use Illuminate\Http\Request;
<<<<<<< HEAD
use App\Models\FarmerProfile;
=======
use App\Models\Farmer\FarmerProfile;
>>>>>>> 77f5d923c32254527826ab3f58a756ddb672ea6e
use App\Orchid\Layouts\Farmer\FarmerCreateLoginLayout;
use App\Orchid\Layouts\Farmer\FarmerCreateProfileLayout;
use App\Orchid\Layouts\Farmer\FarmerCreateSkillLayout;
use App\Orchid\Layouts\Farmer\FarmerCreateAddressLayout;
use App\Orchid\Layouts\Farmer\FarmerCreateSalaryLayout;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Field;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Screen\Action;

class FarmerEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */

    public $name = 'Enroll Farmer';

    /**
     * Display header description.
     *
     * @var string|null
     */

    public $description = 'Fill out all required information.';

    /**
     * Query data.
     *
     * @return array
     */

    public function query(FarmerProfile $farmer_profile): array
    {
        $this->farmer_profile = $farmer_profile;

        if (!$farmer_profile->exists) {
            $this->name = 'Enroll Farmer';
            $this->description = 'Enroll New Farmer';
        }

        return [
            'farmer_profile' => $farmer_profile
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
            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm(__('Once the farmer profile is deleted, all of its resources and data will be permanently deleted.'))
                ->method('remove')
                ->canSee($this->farmer_profile->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
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
            /* Layout::block(FarmerCreateLoginLayout::class)
                ->title('Job and Education Information')
                ->description("This information collects farmer's job and education information."), */

            Layout::block(FarmerCreateProfileLayout::class)
                ->title('Personal Information')
                ->description("This information collects farmer's personal information."),
<<<<<<< HEAD
            
            /* Layout::block(FarmerCreateAddressLayout::class)
                ->title('Job and Education Information')
                ->description("This information collects farmer's job and education information."), */
=======

            Layout::block(FarmerCreateAddressLayout::class)
                ->title('Personal Address')
                ->description("This information collects farmer's personal address."),
>>>>>>> 77f5d923c32254527826ab3f58a756ddb672ea6e

            Layout::block(FarmerCreateSkillLayout::class)
                ->title('Job and Education Information')
                ->description("This information collects farmer's job and education information."),

            Layout::block(FarmerCreateSalaryLayout::class)
                ->title('Salary Information')
                ->description("This information collects farmer's salary information."),
        ];
    }

    /**
     * @param FarmerProfile    $farmer_profile
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */

    public function save(FarmerProfile $farmer_profile, Request $request)
    {
        $request->validate([
            'farmer_profile.gender' => [
                'required'
            ],

            'farmer_profile.civil_status' => [
                'required'
            ],

            'farmer_profile.birthday' => [
                'required'
            ],

            'farmer_profile.age' => [
                'required'
            ],

            'farmer_profile.quantity_family_members' => [
                'required'
            ],

            'farmer_profile.quantity_dependents' => [
                'required'
            ],

            'farmer_profile.quantity_working_dependents' => [
                'required'
            ],

            'farmer_profile.highest_educational_status' => [
                'required'
            ],

            'farmer_profile.college_course' => [
                'required'
            ],

            'farmer_profile.current_job' => [
                'required'
            ],

            'farmer_profile.farming_years' => [
                'required'
            ],

            'farmer_profile.usual_crops_planted' => [
                'required'
            ],

            'farmer_profile.affiliated_organization' => [
                'required'
            ],

            'farmer_profile.tesda_training_joined' => [
                'required'
            ],

            'farmer_profile.nc_passer_status' => [
                'required'
            ],

            'farmer_profile.salary_periodicity' => [
                'required'
            ],

            'farmer_profile.estimated_salary' => [
                'required'
            ],

            'farmer_profile.social_status' => [
                'required'
            ],

            'farmer_profile.social_status_reason' => [
                'required'
            ]

        ]);

        $farmerprofileData = $request->get('farmer_profile');

        $farmer_profile
            ->fill($farmerprofileData)
            ->save();

        Toast::info(__('Farmer Profile was saved'));

        return redirect()->route('platform.farmer.profile.view.all');
    }

    /**
     * @param FarmerProfile $farmer_profile
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */

    public function remove(FarmerProfile $farmer_profile)
    {
        $farmer_profile->delete();

        Toast::info(__('Farmer Profile was removed successfully'));

        return redirect()->route('platform.farmer.profile.view.all');
    }
}
