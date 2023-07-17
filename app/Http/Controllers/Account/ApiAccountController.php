<?php

namespace App\Http\Controllers\Account;

use App\Bus\CommandBus;
use App\Commands\AccountStoreCommand;
use App\Enums\AccountStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountStoreRequest;
use App\Listeners\AccountCreatedNotification;
use App\Models\Account;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\Response;

//https://laravel.com/docs/10.x/controllers#basic-controllers

class ApiAccountController extends Controller
{
    protected CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccountStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $command = new AccountStoreCommand(
            $validated['email'],
            AccountStatus::from($validated['status'])
        );

        $this->commandBus->execute($command);

        try {
            $account = Account::email($validated['email'])->firstOrFail();

            $jsonData = [
                'success' => true,
                'message' => __('message.success'),
                'data' => [
                    'id' => $account->id,
                    'email' => $account->email,
                ]
            ];
            $responseStatus = Response::HTTP_CREATED;
        } catch (ModelNotFoundException $exception) {
            $jsonData = [
                'success' => false,
                'message' => __('message.failed'),
                'errors' => $exception->getMessage()
            ];
            $responseStatus = Response::HTTP_INTERNAL_SERVER_ERROR ;
        }

        return response()->json($jsonData, $responseStatus);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
