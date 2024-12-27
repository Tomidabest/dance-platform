<x-layout>
    <div class = "view_all">
        @foreach ($studios as $studio)
            <h1> 
                <a href="{{route('studios.single', $studio->id)}}"> Studio {{$studio->name}} </a>
            </h1>
            <img 
            src="{{$studio->firstImage() ? asset('storage/' . $studio->firstImage()) : asset('storage/images/studios/placeholder.jpg')}}"
            alt="{{ $studio->name }}" 
            style="width:150px; height:auto;">

            <h2> Location: {{$studio->address}} </h2>
        @endforeach
    </div>

</x-layout>