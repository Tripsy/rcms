<?php

namespace App\Http\Controllers\Item;

use App\Bus\CommandBus;
use App\Commands\AccountCreateCommand;
use App\Enums\AccountStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiItemController extends Controller
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
    public function store(Request $request)
    {
        //TODO verifications
        //TODO exceptions

        $email = $request->input('email');
        $status = AccountStatus::from($request->input('status'));

        $command = new AccountCreateCommand(
            $email,
            $status
        );

        $this->commandBus->execute($command);

        // Handle the response or redirect as needed
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
