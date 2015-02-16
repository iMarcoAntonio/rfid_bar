<?php

    class BulkController extends BaseController {

        function index() {
            return View::make('bulks.index',array(
                "families"=>Family::get(),
            ));
        }

        public function store() {

        }

        public function bulksDatatables() {
            return Bulk::all();
        }
    }
