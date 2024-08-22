<table>
    {{-- <thead>
        <tr>
            <th>Email</th>
        </tr>
    </thead> --}}
    <tbody>
        @foreach($emails as $email)
            <tr>
                <td>{{$email['email']}}</td>
            </tr>
        @endforeach
    </tbody>
</table>