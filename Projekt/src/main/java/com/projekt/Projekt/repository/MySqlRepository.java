package com.projekt.Projekt.repository;

import com.projekt.Projekt.model.Person;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.Optional;

@Repository
public interface MySqlRepository extends JpaRepository<Person, Integer> {
    Optional<Person> findByApiKey(String apiKey);
}


