<x-layout>
    <div class = "view_all">
        @foreach ($studios as $studio)
            <h1> 
                <a href="{{route('studios.single', $studio->id)}}"> Studio {{$studio->name}} </a>
            </h1>
            <h2> Location: {{$studio->address}} </h2>
        @endforeach
    </div>

</x-layout>