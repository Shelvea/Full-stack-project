<x-app-layout>
    <x-slot name="title">Home</x-slot>
    
    <div class="row justify-content-center my-5 pt-5">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    {{ __("User You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
