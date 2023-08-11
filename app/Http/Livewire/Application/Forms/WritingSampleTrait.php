<?php

namespace App\Http\Livewire\Application\Forms;

use Closure;
use App\Rules\MaxWordCount;
use Illuminate\Support\HtmlString;
use Livewire\Component as Livewire;
use App\Forms\Components\WordTextArea;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Wiebenieuwenhuis\FilamentCharCounter\Textarea;

trait WritingSampleTrait{

    public function getWritingSampleForm()
    {
        return [
            Placeholder::make('section_writing_sample')
                ->label('')
                ->content(new HtmlString('* This section is to be completed by the applicant. Select one of the topics below.  Write a complete essay with a maximum of 250 words.')),
            WordTextArea::make('writing_sample_essay')
                ->label(new HtmlString('<div class="font-medium text-gray-700">

                        <section class="space-y-4">
                            <h3 class="font-bold font-heading text-primary-red">What matters to you? How does that motivate you, impact your life, your outlook, and/or your identity?</h3>
                            <p class="font-medium">What matters to you might be an activity, an idea, a goal, a place, and/or a thing.</p>
                            <p class="font-medium"> This essay should be about you and your thoughts. It should not be about the life of another person you admire.</p>
                        </section>
                        <section class="mt-8">
                            <h3 class="text-xl font-bold text-center text-gray-900 font-heading">--OR--</h3>
                        </section>
                        
                        <section class="mt-8 space-y-4">
                            <h3 class="relative text-primary-red ">
                                <sup class="absolute font-medium text-danger-700 whitespace-nowrap" style="left: -10px; top: 7px">*</sup>
                                <span class="font-bold">What is an obstacle you have overcome?</span>
                            </h3>
                            <p>
                                Explain how the obstacle impacted you and how you handled the situation (i.e., positive and/or negative attempts along the way or maybe how you are still working on it).
                            </p>
                            <p>
                                Include what you have learned from the experience and how you have applied (or might apply) this to another situation in your life.
                            </p>
                        </section>
                    </div>'))
                ->helperText('Please limit your answer to 250 words.')
                ->rows(15)
                ->lazy()
                ->required()
                ->wordLimit(250)
                ->rules([
                    new MaxWordCount(250,300)
                ])
                ->afterStateUpdated(function(Livewire $livewire, WordTextArea $component, Closure $get, $state){
                    $livewire->validateOnly($component->getStatePath());
                    $this->autoSave('writing_sample_essay', $state);
                }),
            Checkbox::make('writing_sample_essay_acknowledgement')
                ->columnSpan('full')
                ->validationAttribute('checkbox')
                ->label('By checking this box, I (applicant) declare that to
                the best of my knowledge, the information provided in the application submitted to
                St. Ignatius College Preparatory on this online application is true and complete.
                ')
                ->rules(['accepted'])
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