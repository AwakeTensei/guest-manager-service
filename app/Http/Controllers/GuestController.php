<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Repositories\GuestRepository;
use App\Services\GuestService;

class GuestController extends Controller
{
    protected $guestRepository;
    protected $guestService;

    public function __construct(GuestRepository $guestRepository, GuestService $guestService)
    {
        $this->guestRepository = $guestRepository;
        $this->guestService = $guestService;
    }

    public function index()
    {
        $guests = $this->guestRepository->all();
        return response()->json($guests);
    }

    public function store(StoreGuestRequest $request)
    {
        $data = $request->validated();
        $data['country'] = $request->input('country') ?? $this->guestService->determineCountryFromPhone($data['phone']);
        $this->guestRepository->create($data);

        return response()->json(['message' => 'Guest created'], 201);
    }

    public function show($id)
    {
        $guest = $this->guestRepository->find($id);
        if (!$guest) {
            return response()->json(['message' => 'Guest not found'], 404);
        }

        return response()->json($guest);
    }

    public function update(UpdateGuestRequest $request, $id)
    {
        $data = $request->validated();
        if ($request->has('phone')) {
            $data['country'] = $request->input('country') ?? $this->guestService->determineCountryFromPhone($data['phone']);
        }

        if (!$this->guestRepository->update($id, $data)) {
            return response()->json(['message' => 'Guest not found'], 404);
        }

        return response()->json(['message' => 'Guest updated']);
    }

    public function destroy($id)
    {
        if (!$this->guestRepository->delete($id)) {
            return response()->json(['message' => 'Guest not found'], 404);
        }

        return response()->json(['message' => 'Guest removed']);
    }
}
