package com.projekt.Projekt.controller.errors;

import jakarta.servlet.http.HttpServletRequest;
import org.springframework.core.Ordered;
import org.springframework.core.annotation.Order;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.ControllerAdvice;
import org.springframework.web.bind.annotation.ExceptionHandler;
import org.springframework.web.servlet.mvc.method.annotation.ResponseEntityExceptionHandler;

import java.sql.SQLIntegrityConstraintViolationException;
import java.util.NoSuchElementException;

@Order(Ordered.HIGHEST_PRECEDENCE)
@ControllerAdvice()
public class RestExceptionHandler extends ResponseEntityExceptionHandler {

    @ExceptionHandler(SecurityException.class)
    public ResponseEntity<Object> handleSecurityException(HttpServletRequest req, SecurityException ex){
        ErrorResponse response = new ErrorResponse(HttpStatus.UNAUTHORIZED);
        response.setMessage("Invalid API key provided. Please provide a valid API key.");
        return buildResponseEntity(response);
    }

    @ExceptionHandler(NoSuchElementException.class)
    public ResponseEntity<Object> handleNoSuchElementException(HttpServletRequest req, NoSuchElementException ex){
        ErrorResponse response = new ErrorResponse(); // Użyj konstruktora domyślnego
        response.setStatus(HttpStatus.NOT_FOUND);
        response.setMessage("The row for person is not existent: " + req.getRequestURI());
        return buildResponseEntity(response);
    }


    private ResponseEntity<Object> buildResponseEntity(ErrorResponse errorResponse){

        return new ResponseEntity<Object>(errorResponse, errorResponse.getStatus());
    }

}
