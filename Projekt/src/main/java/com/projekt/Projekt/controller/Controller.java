package com.projekt.Projekt.controller;

import com.projekt.Projekt.controller.errors.ErrorResponse;
import com.projekt.Projekt.model.Person;
import com.projekt.Projekt.repository.MySqlRepository;
import jakarta.persistence.NonUniqueResultException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.*;

@RestController
public class Controller {

    private static final String MAIN_API_KEY = "klucz";

    @Autowired
    MySqlRepository mySqlRepository;

    @GetMapping("/get-all-persons")
    public List<Person> getAllPersons() {
        try {
            return mySqlRepository.findAll();
        } catch (Exception e) {
            e.printStackTrace();
            throw e;
        }
    }

    @GetMapping("/get-all-persons/{identity}")
    public ResponseEntity<?> getSinglePerson(@PathVariable("identity") Integer id) {
        Person person = mySqlRepository.findById(id)
                .orElseThrow(() -> new NoSuchElementException("Person not found with ID: " + id));
        return ResponseEntity.ok(person);
    }

    @DeleteMapping("/remove/{id}")
    public boolean deleteRow(@PathVariable("id") Integer id, @RequestHeader("api-key") String apiKey) {
        verifyApiKey(apiKey);
        if (!mySqlRepository.findById(id).equals(Optional.empty())) {
            mySqlRepository.deleteById(id);
            return true;
        }
        return false;
    }

    @PutMapping("/update/{id}")
    public Person updatePerson(@PathVariable("id") Integer id,
                               @RequestBody Map<String, String> body,
                               @RequestHeader("api-key") String apiKey) {
        verifyApiKey(apiKey);
        Person current = mySqlRepository.findById(id).orElse(null);
        if (current != null) {
            current.setImie(body.get("imie"));
            current.setWiek(Integer.parseInt(body.get("wiek")));
            current.setNazwisko(body.get("nazwisko"));
            mySqlRepository.save(current);
        }
        return current;
    }

    @PostMapping("/add")
    public Person create(@RequestBody Map<String, String> body, @RequestHeader(value = "api-key", required = false) String apiKey) {
        System.out.println("Received request to create a new person with API key: " + apiKey);
        System.out.println("Number of persons in the database: " + mySqlRepository.count());

        if (mySqlRepository.count() == 0) {
            apiKey = MAIN_API_KEY;
        } else {
            verifyApiKey(apiKey);
        }

        System.out.println("API key after checking the database: " + apiKey);

        String imie = body.get("imie");
        Integer wiek = Integer.parseInt(body.get("wiek"));
        String nazwisko = body.get("nazwisko");
        String generatedApiKey = UUID.randomUUID().toString();
        Person nowaOsoba = new Person(generatedApiKey, wiek, imie, nazwisko);

        return mySqlRepository.save(nowaOsoba);
    }




    private void verifyApiKey(String apiKey) {
        // Tutaj możesz zaimplementować logikę weryfikacji klucza API, np. sprawdzenie w bazie danych
        if (!isValidApiKey(apiKey)) {
            throw new SecurityException("Invalid API key");
        }
    }

    private boolean isValidApiKey(String apiKey) {
        try {
            // Sprawdzenie, czy klucz API jest unikatowy w bazie danych
            Optional<Person> person = mySqlRepository.findByApiKey(apiKey);
            return person.isPresent();
        } catch (NonUniqueResultException e) {
            // Jeśli wystąpił wyjątek NonUniqueResultException, oznacza to, że klucz API nie jest unikatowy
            // Możemy tutaj zaimplementować odpowiednie logowanie
            System.err.println("Wystąpił problem z kluczem API: " + apiKey + ". Klucz nie jest unikatowy.");
            e.printStackTrace();
            return false;
        }
    }

}