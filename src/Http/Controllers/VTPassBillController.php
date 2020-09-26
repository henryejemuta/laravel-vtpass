<?php
/**
 * Created By: Henry Ejemuta
 * PC: Enrico Systems
 * Project: laravel-vtpass
 * Company: Stimolive Technologies Limited
 * Class Name: VTPassBillController.php
 * Date Created: 9/26/20
 * Time Created: 7:08 PM
 */

namespace HenryEjemuta\LaravelVTPass\Http\Controllers;


class VTPassBillController extends Controller {
    public function index() {
        //
    }

    public function show() {
        //
    }

    public function store() {
        if (! auth()->check()) {
            abort (403, 'Only authenticated users can create new posts.');
        }
    }
}
