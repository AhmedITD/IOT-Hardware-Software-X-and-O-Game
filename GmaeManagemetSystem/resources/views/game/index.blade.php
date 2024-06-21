@extends('game/layout')
@section('title', 'XO Game')
@section('style', asset('assets/css/index.css'))
@section('content')
@section('bodyEvent', "onloadBord()")
    <div id="all-content">

        <!-- X/O BOARD -->
        <div id="main-content">

            <div id="header-and-board-wrapper">

                <!-- TOP HEADER -->
                <div id="header">
                    <h2 id="instruction">
                        X turn
                    </h2>
                </div>
                <!--// TOP HEADER //-->

                <!-- BOARD -->
                <div class="grid-container">
                    <div class="square" value="1" style="border-top: none; border-left: none">
                        <h3 class="square-content">

                        </h3>
                    </div>

                    <div class="square" value="2" style="border-top: none">
                        <h3 class="square-content">

                        </h3>
                    </div>

                    <div class="square" value="3" style="border-top: none; border-right: none">
                        <h3 class="square-content">

                        </h3>
                    </div>

                    <div class="square" value="4" style="border-left: none">
                        <h3 class="square-content">

                        </h3>
                    </div>

                    <div class="square" value="5" style="">
                        <h3 class="square-content">

                        </h3>
                    </div>

                    <div class="square" value="6" style="border-right: none">
                        <h3 class="square-content">

                        </h3>
                    </div>

                    <div class="square" value="7" style="border-bottom: none; border-left: none">
                        <h3 class="square-content">

                        </h3>
                    </div>

                    <div class="square" value="8" style="border-bottom: none">
                        <h3 class="square-content">

                        </h3>
                    </div>

                    <div class="square" value="9" style="border-bottom: none; border-right: none">
                        <h3 class="square-content">

                        </h3>
                    </div>

                </div>
                <!-- BOARD -->

                <!-- FOOTER -->
                <div id="footer">
                    <button class="action-btn" id="reset-btn">
                        reset
                    </button>
                </div>
                <!-- FOOTER -->

            </div>
        </div>
        <!--// X/O BOARD //-->
 
@section('script', asset('assets\js\index.js'))
@endsection