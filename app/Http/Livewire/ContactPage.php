<?php

namespace App\Http\Livewire;

use Auth;
use Mail;
use Closure;
use App\Models\Child;
use App\Models\Inquiry;
use App\Models\Parents;
use Livewire\Component;
use App\Enums\Departments;
use App\Mail\ContactInquiry;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;

class ContactPage extends Component implements HasForms
{
    use InteractsWithForms;

    public $data = [];

    public $users = [];

    public $sent = false;
    
    public function render()
    {
        return view('livewire.contact-page');
    }

    public function mount()
    {
        $this->getAccountUsers();
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('account')
                ->label('Family Member')
                ->options($this->users)
                ->lazy()
                ->afterStateUpdated(function(Closure $set, $state){
                    if($state){
                        $arr = explode('-', $state);
                        $model = $arr[0];
                        $id = $arr[1];
                        $record =  $model == 'parent' ? Parents::find($id) : Child::find($id);

                        
                        $set('email', $record->personal_email);
                    }
                })->required(),
            TextInput::make('email')
                ->lazy()
                ->email()
                ->required(),
            Select::make('department')
                ->label('Department')
                ->required()
                ->options(Departments::asSameArray()),
            TextInput::make('subject')
                ->required(),
            Textarea::make('message')
                ->required()
                ->rows(5),
            FileUpload::make('attachments')
                ->multiple()
                ->maxSize(25000)
                ->enableOpen()
                ->enableDownload()
                ->directory("attachments/" . date('Ymdhis'))
                ->visibility('public')
                // ->preserveFilenames()
                ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                    // TODO: Clean Strings:
                    return (string) clean_string($file->getClientOriginalName());
                })
        ];
    }

    protected function getFormStatePath(): string 
    {
        return 'data';
    }

    public function getAccountUsers()
    {
        $account_id = accountId();
        $users = [];
        $parents = Parents::where('account_id', $account_id)->get();
        $children = Child::where('account_id', $account_id)->get();


        foreach($parents as $parent)
        $users['parent-' . $parent->id] = $parent->getFullName();

        foreach($children as $child)
        $users['child-' . $child->id] = $child->getFullName();

        $this->users = $users;
    }

    public function submit()
    {
        $data = $this->form->getState();

        $data['user_id'] = Auth::id();

        $arr = explode('-', $data['account']);
        $model = $arr[0];
        $id = $arr[1];
        $record =  $model == 'parent' ? Parents::find($id) : Child::find($id);
        
        $data['account'] = $record->getFullName();

        $inquiry = Inquiry::create($data);

        $recipients = [];

        if($data['department'] == Departments::Admissions){
            $recipients[] = 'admissions@siprep.org';
        }

        elseif($data['department'] == Departments::Academics){
            $recipients[] = 'ggalletta@siprep.org';
            $recipients[] = 'pcollins@siprep.org';
        }

        elseif($data['department'] == Departments::TechSupport){
            $recipients[] = 'mysi_admin@siprep.org';
        }

        else{
            $recipients[] = 'mysi_admin@siprep.org';
        }
        
        try {
            Mail::to($recipients)->send(new ContactInquiry($inquiry));
        } catch (\Throwable $th) {
            throw $th;
        }

        $this->form->fill();
        
        $this->sent = true;


    }
}
