<div>
    <div>
    
        <div class="flex justify-between">
            <h2 class="text-2xl font-semibold font-heading text-primary-blue">
                Registration
            </h2>
        </div>


    
        <div  class="pb-32 mt-8">
    
            
            <div>
                <form wire:submit.prevent="submit" novalidate>
    
                    {{ $this->form }}
        
                    <div class="flex justify-center mt-8">
                        <button type="submit" class="btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>


