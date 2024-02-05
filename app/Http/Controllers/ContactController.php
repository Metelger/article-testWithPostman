<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Services\ContactService;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index()
    {
        return $this->contactService->getAllContacts();
    }

    public function show($id)
    {
        return $this->contactService->getContactById($id);
    }

    public function store(ContactRequest $request)
    {
        $data = $request->validated();

        return $this->contactService->createContact($data);
    }

    public function update(ContactRequest $request, $id)
    {
        $data = $request->validated();

        return $this->contactService->updateContact($id, $data);
    }

    public function destroy($id)
    {
        return $this->contactService->deleteContact($id);
    }

    public function bulkDestroy()
    {
        return $this->contactService->bulkDestroy();
    }
}
