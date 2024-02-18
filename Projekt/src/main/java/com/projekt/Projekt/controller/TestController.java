package com.projekt.Projekt.controller;

import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RestController;

@RestController
public class TestController {

    @GetMapping("/")
    public String getHome(){
        return "Home";
    }

    @GetMapping("/test")
    public String getTest(){
        return "Test";
    }
}
