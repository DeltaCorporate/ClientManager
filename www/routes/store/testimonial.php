<?php


use App\Controllers\Store\TestimonialsController;
use Core\Route;
use Core\Router;

Router::post( (new Route([TestimonialsController::class,'likeGestion']))->path("/store/testimonial/likeGestion")->name("store.testimonial.likeGestion")->middleware("auth"));
Router::post( (new Route([TestimonialsController::class,'comment']))->path("/store/testimonial/comment")->name("store.testimonial.comment")->middleware('auth'));
Router::post( (new Route([TestimonialsController::class,'update']))->path("/store/testimonial/update")->name("store.testimonial.update")->middleware('auth'));
Router::post( (new Route([TestimonialsController::class,'delete']))->path("/store/testimonial/delete")->name("store.testimonial.delete")->middleware('auth'));
