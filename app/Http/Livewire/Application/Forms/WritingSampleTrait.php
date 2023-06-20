<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use Illuminate\Support\HtmlString;
use Livewire\Component as Livewire;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Wiebenieuwenhuis\FilamentCharCounter\Textarea;

trait WritingSampleTrait{

    public function getWritingSampleForm()
    {
        return [
            Textarea::make('writing_sample_essay')
                ->label(new HtmlString('<div>
                        <section class="flex">
                            <h4 class="font-bold font-heading text-primary-red">DIRECTIONS:</h4>
                            <div class="pl-4 space-y-4 text-sm">
                                <p>
                                    This section is to be completed by the student. Please prepare and save your answers in a word document first. Then, copy and paste your answers on this page. This will ensure you will always have a back up of your work. Required fields are in <strong class="text-primary-500">red.</strong>
                                </p>
                                <p>
                                    Select and complete one of the topics below. (1500 characters maximum, approximately 250 words)
                                </p>
                            </div>
                        </section>

                        <section class="mt-8 space-y-4">
                            <h3 class="font-bold font-heading text-primary-red">What matters to you? How does that motivate you, impact your life, your outlook, and/or your identity?</h3>
                            <p>What matters to you might be an activity, an idea, a goal, a place, and/or a thing.</p>
                            <p> <strong>PLEASE NOTE:</strong> This essay should be about you and your thoughts. It should not be about the life of another person you admire.</p>
                        </section>
                        <section class="mt-8">
                            <h3 class="text-xl font-bold text-center text-gray-900 font-heading">--OR--</h3>
                        </section>
                        
                        <section class="mt-8 space-y-4">
                            <h3 class="font-bold font-heading text-primary-red">
                                An obstacle you have overcome.
                            </h3>
                            <p>
                                Explain how the obstacle impacted you and how you handled the situation (i.e., positive and/or negative attempts along the way or maybe how you`re still working on it) .
                            </p>
                            <p>
                                Include what you have learned from the experience and how you have applied (or might apply) this to another situation in your life.
                            </p>
                        </section>
                    </div>'))
                ->helperText('Up to 1750 characters only.')
                ->maxLength(1750)
                ->placeholder('Start writing here ...')
                ->rows(15)
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('writing_sample_essay', $state);
                }),
            Checkbox::make('writing_sample_essay_acknowledgement')
                ->columnSpan('full')
                ->label('By clicking this box, I (applicant) declare that to
                the best of my knowledge, the information provided in the application submitted to
                St. Ignatius College Preparatory on this online application is true and complete.
                ')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('writing_sample_essay_acknowledgement', $state);
                }),
            TextInput::make('writing_sample_essay_by')
                ->label('Submitted By')
                ->lazy()
                ->required()
                ->afterStateUpdated(function($state){
                    $this->autoSave('writing_sample_essay_by', $state);
                }),
        ];
    }
}