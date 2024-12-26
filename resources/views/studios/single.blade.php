<x-layout>
    <div class="single_view">
        <img 
        src="{{$studio->firstImage() ? asset('storage/' . $studio->firstImage()) : asset('storage/images/studios/placeholder.jpg')}}"
        alt="{{ $studio->name }}" 
        style="width:500px; height:auto;">
        <h1> Studio {{$studio->name}} </h1>
        <h2> Location: {{$studio->address}} </h2>
        <h2> Phone: {{$studio->phone}} </h2>
        <h2> Email: {{$studio->email}} </h2>
        <h3> Description: {{$studio->description}} </h3>

        <h1> Instructors </h1>
        <ul>
            @foreach ($instructors as $instructor)
                <li> {{$instructor->user->first_name}} {{$instructor->user->last_name}} : Experience ({{$instructor->experience}}) yrs / Expertise : ({{$instructor->dance_expertise}}) </li>
            @endforeach
        </ul>
            
        <h1> Classes </h1>

        <ul>
            @foreach ($classes as $class)
                <li> Name: {{$class->name}} </li>
                <li> Genre: {{$class->genre}} </li>
                <li> Description: {{$class->description}} </li>
                <li> Price: {{$class->price}} lv </li>
                <li> Start: {{$class->time_start}} </li>
                <li> End: {{$class->time_ends}} </li>
                <li> Availability: {{$class->availability}} </li>

                @if($class->availability > 0)
                    <form action="{{route('class.book', $class->id)}}" method='POST'>
                        @csrf
                        <button class="search-register__button" type='submit'>Book</button>
                    </form> 
                @endif
            @endforeach
        </ul>
    </div>
</x-layout>