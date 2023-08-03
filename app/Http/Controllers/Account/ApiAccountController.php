<?php

namespace App\Http\Controllers\Account;

use App\Bus\CommandBus;
use App\Commands\AccountStoreCommand;
use App\Enums\AccountStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountStoreRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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

        return response()->json([
            'success' => true,
            'message' => __('message.success'),
            'data' => [
                'email' => $validated['email'],
            ]
        ], Response::HTTP_CREATED);
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
