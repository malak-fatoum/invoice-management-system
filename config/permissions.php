<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/* ---------- Roles ---------- */

function isAdmin()
{
    return isset($_SESSION["role"]) && $_SESSION["role"] == "admin";
}

function isAccountant()
{
    return isset($_SESSION["role"]) && $_SESSION["role"] == "accountant";
}

/* ---------- Users ---------- */

function canManageUsers()
{
    return isAdmin();
}

function canViewPassword()
{
    return isAdmin();
}

/* ---------- Customers ---------- */

function canAddCustomer()
{
    return isAdmin() || isAccountant();
}

function canEditCustomer()
{
    return isAdmin();
}

function canDeleteCustomer()
{
    return isAdmin();
}

/* ---------- Invoices ---------- */

function canAddInvoice()
{
    return isAdmin() || isAccountant();
}

function canEditInvoice()
{
    return isAdmin();
}

function canDeleteInvoice()
{
    return isAdmin();
}

function canMarkPaid()
{
    return isAdmin();
}