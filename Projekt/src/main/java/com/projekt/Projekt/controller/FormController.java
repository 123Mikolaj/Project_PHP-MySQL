package com.projekt.Projekt.controller;

import com.projekt.Projekt.model.Person;
import com.projekt.Projekt.repository.MySqlRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;

import java.util.UUID;

@Controller
@RequestMapping("/")
public class FormController {

    @Autowired
    MySqlRepository mySqlRepository;

    @GetMapping("/form")
    public String showForm(Model model) {
        model.addAttribute("person", new Person());
        return "Form.html";
    }

    @PostMapping("/add-person")
    public String addPerson(@ModelAttribute Person person) {
        String generatedApiKey = UUID.randomUUID().toString();
        person.setApiKey(generatedApiKey);
        mySqlRepository.save(person);
        return "redirect:/get-all-persons";
    }

}

