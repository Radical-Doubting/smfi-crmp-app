<?php

namespace App\Actions\FarmerReport\Api;

use App\Http\Resources\FarmerReport\FarmerReportResource;
use App\Models\Farmer\Farmer;
use App\Models\FarmerReport\FarmerReport;
use App\Models\Farmland\Farmland;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class SubmitFarmerReport
{
    use AsAction;

    public function __construct(
        protected ValidateSeedStage $validateSeedStage
    ) {
    }

    public function handle(
        Farmer $farmer,
        array $farmerReportData,
        UploadedFile $imageFile
    ): FarmerReport {
        $farmland = Farmland::findOrFail($farmerReportData['farmlandId']);

        $nextSeedStage = $this->validateSeedStage->handle(
            $farmer,
            $farmland
        );

        $farmerReport = FarmerReport::create([
            'reported_by' => $farmer->id,
            'seed_stage_id' => $nextSeedStage->id,
            'farmland_id' => $farmland->id,
            'crop_id' => $farmerReportData['cropId'],
            'volume_kg' => $farmerReportData['volumeKg'],
        ]);

        Storage::put('reports', $imageFile);

        return $farmerReport;
    }

    private function validateReportData(array $farmerReportData): void
    {
        $validator = Validator::make($farmerReportData, [
            'farmlandId' => [
                'required',
                'integer',
            ],
            'cropId' => [
                'required',
                'integer',
            ],
            'volumeKg' => [
                'numeric',
                'nullable',
            ],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @OA\Post(
     *     path="/farmer-reports",
     *     description="Submit a farmer report with the logged in farmer. It needs both image file and data JSON.",
     *     tags={"farmer-reports"},
     *     @OA\RequestBody(
     *       required=true,
     *        @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="proof image to upload",
     *                     property="image",
     *                     type="file",
     *                ),
     *                 required={"image"}
     *             )
     *         ),
     *        @OA\JsonContent(
     *          @OA\Property(
     *             property="farmerReport",
     *             @OA\Property(property="farmlandId", type="int", format="int", example="1"),
     *             @OA\Property(property="cropId", type="int", format="int", example="1"),
     *             @OA\Property(property="volumeKg", type="double", format="double", example="10.23"),
     *          )
     *       ),
     *     ),
     *     @OA\Response(response="200", description="The created farmer report", @OA\JsonContent()),
     *     @OA\Response(response="422", description="Validation errors occured", @OA\JsonContent()),
     * )
     */
    public function asController(ActionRequest $request): JsonResponse
    {
        /**
         * @var Farmer
         */
        $farmer = auth('api')->user();

        $farmerReportData = json_decode($request->get('data'), true);
        $this->validateReportData($farmerReportData);

        $imageFile = $request->file('image');

        try {
            $createdFarmerReport = $this->handle($farmer, $farmerReportData, $imageFile);

            return response()->json(
                new FarmerReportResource(
                    $createdFarmerReport->fresh()
                )
            );
        } catch (Exception $exception) {
            return abort(400, $exception->getMessage());
        }
    }

    public function rules(): array
    {
        return [
            'image' => [
                'mimes:jpeg,jpg,bmp,png',
                'max:10240',
            ],
            'data' => [
                'required',
                'json',
            ],
        ];
    }
}
