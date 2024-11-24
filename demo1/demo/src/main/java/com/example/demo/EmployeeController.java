package com.example.demo;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/employees")
public class EmployeeController {

    @Autowired
    private EmployeeRepository employeeRepository;

    @PostMapping
    public ResponseEntity<Employee> addEmployee(@RequestBody Employee employee) {
        Employee savedEmployee = employeeRepository.save(employee);
        return new ResponseEntity<>(savedEmployee, HttpStatus.CREATED);
    }

    @GetMapping
    public List<Employee> getAllEmployees() {
        return employeeRepository.findAll();
    }
}