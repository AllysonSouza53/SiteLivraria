<?php

final class InicialController extends Controller
{
    public static function index(): void
    {
        parent::isProtected();       

        parent::render('Inicial/home.php', null);
    }
}
