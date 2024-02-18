package com.projekt.Projekt.model;

import jakarta.persistence.*;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import org.hibernate.validator.constraints.Range;

@Entity
@Table(name="osoby")
public class Person {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Integer id;

    @Column(name = "api_key")
    private String apiKey;

    @NotNull
    @Range(min = 1, max = 150, message = "Age must be between 1 and 150")
    private Integer wiek;

    @NotBlank(message = "Name is mandatory")
    private String imie;

    @NotBlank(message = "Surname is mandatory")
    private String nazwisko;

    public Person() {
    }

    public Person(String apiKey, Integer wiek, String imie, String nazwisko) {
        this.apiKey = apiKey;
        this.wiek = wiek;
        this.imie = imie;
        this.nazwisko = nazwisko;
    }

    public Integer getId() {
        return id;
    }

    public Integer getWiek() {
        return wiek;
    }

    public void setWiek(Integer wiek) {
        this.wiek = wiek;
    }

    public String getImie() {
        return imie;
    }

    public void setImie(String imie) {
        this.imie = imie;
    }

    public String getNazwisko() {
        return nazwisko;
    }

    public void setNazwisko(String nazwisko) {
        this.nazwisko = nazwisko;
    }

    public String getApiKey() {
        return apiKey;
    }

    public void setApiKey(String apiKey) {
        this.apiKey = apiKey;
    }
}



