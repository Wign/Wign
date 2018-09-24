<?php
/**
 * Created by PhpStorm.
 * User: ken
 * Date: 20/09/2018
 * Time: 12.22
 */
@if (count($errors->all()))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>