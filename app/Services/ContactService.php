<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Database\QueryException;

class ContactService
{
    public function getAllContacts()
    {
        $getAllContacts = Contact::all();
        if(json_decode($getAllContacts) != null)
        {
            return response()->json($getAllContacts, 200);
        } else {
            $error = [
                "error" => true,
                "description" => "Error listing contacts: contacts not found."
            ];
            return response()->json($error, 500);
        }
    }

    public function getContactById($id)
    {
        $getContact = Contact::find($id);

        if(json_decode($getContact) != null)
        {
            return response()->json($getContact, 200);
        } else {
            $error = [
                "error" => true,
                "description" => "Error listing contact: id not found."
            ];
            return response()->json($error, 500);
        }
    }

    public function createContact($data)
    {
        $phoneData = [
            'countryCode' => $data['phone']['countryCode'] ?? null,
            'regionCode' => $data['phone']['regionCode'] ?? null,
            'number' => $data['phone']['number'] ?? null,
        ];
        
        $contactData = [
            'name' => $data['name'] ?? null,
            'phone' => $phoneData,
            'email' => $data['email'] ?? null,
            'document' => $data['document'] ?? null,
        ];

        if($phoneData['countryCode'] == null || $phoneData['regionCode'] == null || $phoneData['number'] == null)
        {
            $nullItem = null;

            if (is_null($phoneData['countryCode'])) {
                $nullItem = 'countryCode';
            } elseif (is_null($phoneData['regionCode'])) {
                $nullItem = 'regionCode';
            } elseif (is_null($phoneData['number'])) {
                $nullItem = 'number';
            }

            $error = [
                "error" => true,
                "description" => "The phone object must be filled in correctly! The parameter " . $nullItem . " cannot be null.",
            ];
            return response()->json($error, 500);
        }
    
        try {
            $contact = Contact::create($contactData);
            return response()->json($contact, 201); // 201 Created
        } catch (\Exception $e) {
            $error = [
                "error" => true,
                "description" => "Error creating contact: " . $e->errorInfo[2],
            ];
            return response()->json($error, 500); // 500 Internal Server Error
        }
    }
    
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
            return response()->json($error, 500); // 500 Internal Server Error
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
            return response()->json($error, 500); // 500 Internal Server Error
        }
    }

    public function bulkDestroy()
    {
        $allContacts = $this->getAllContacts();

        //Gambiarra pra tratar o array que deveria vir de getAllContacts
        $allContacts = json_encode($allContacts);
        $allContacts = json_decode($allContacts);
        $allContacts = $allContacts->original;
    
        if(gettype($allContacts) == "array")
        {
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
        } else {
            $error = [
                "error" => true,
                "description" => "Error deleting contacts: contacts not found.",
            ];
            return response()->json($error, 500);
        }
    }
}