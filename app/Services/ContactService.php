<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Database\QueryException;
use App\Http\Resources\ContactResource;

class ContactService
{
    public function getAllContacts()
    {
        $getAllContacts = Contact::all();

        if ($getAllContacts->isNotEmpty()) {
            $formatContacts = ContactResource::collection($getAllContacts);
            return response()->json($formatContacts, 200);
        }

        $message = [
            "status" => "OK",
            "description" => "Contact list is empty"
        ];

        return response()->json($message, 200);
    }


    public function getContactById($id)
    {
        $getContact = Contact::find($id);

        if ($getContact !== null) {

            return response()->json($getContact, 200);
        }
        $message = [
            "status" => "OK",
            "description" => "Id not found"
        ];
        return response()->json($message, 200);
    }

    public function createContact($data)
    {
        $phoneData = [
            "countryCode" => $data["phone"]["countryCode"],
            "regionCode" => $data["phone"]["regionCode"],
            "number" => $data["phone"]["number"]
        ];

        $contactData = [
            "name" => $data["name"],
            "phone" => $phoneData,
            "email" => $data["email"],
            "document" => $data["document"],
        ];

        try {
            $contact = Contact::create($contactData);
            return response()->json($contact, 201);
        } catch (\Exception $e) {
            $error = [
                "error" => true,
                "description" => "Error creating contact",
            ];
            return response()->json($error, 500);
        }
    }

    //Adicionar melhores tratativas. ID inexistente retornar 200, tratar campos do input
    public function updateContact($id, $data)
    {
        try {
            $contact = Contact::findOrFail($id);
            $contact->update($data);
            $message = [
                "status" => "OK",
                "message" => "Contact update!",
                "contact" => $contact
            ];
            return response()->json($message, 200);
        } catch (\Exception $e) {
            $error = [
                "error" => true,
                "description" => "Error updating contact: id not found.",
            ];
            return response()->json($error, 500);
        }
    }

    public function deleteContact($id)
    {
        try {
            $contact = Contact::findOrFail($id);
            $contact->delete();
            $message = [
                "status" => "OK",
                "message" => "Contact deleted!"
            ];
            return response()->json($message, 200);
        } catch (\Exception $e) {
            $error = [
                "error" => true,
                "description" => "Error deleting contact: id not found.",
            ];
            return response()->json($error, 500);
        }
    }

    public function bulkDestroy()
    {
        $allContacts = Contact::all();
        if ($allContacts->isNotEmpty()) {
            $totalDeletes = count($allContacts);
            foreach ($allContacts as $ids) {
                $ids = $ids->id;
                $this->deleteContact($ids);
            }
            $message = [
                "status" => "OK",
                "message" => $totalDeletes . " contacts deleted!"
            ];
            return response()->json($message, 200);
        }
        $error = [
            "status" => "OK",
            "description" => "Contact list is empty. No contacts to delete",
        ];
        return response()->json($error, 200);
    }
}
