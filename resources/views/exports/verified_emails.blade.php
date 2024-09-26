<table>
    <thead>
        <tr>
            <th>EMAIL</th>
            @if ($isExport)
                <th>RESULT</th>
                <th>REASON</th>
                <th>STATUS CODE </th> 
                <th>ROLE </th>
                <th>FREE </th> 
                <th>DOMAIN </th>
            @endif 
        </tr>
    </thead>
    <tbody>
        @foreach($emails as $email)
            @php
              $domain = explode('@', $email['email'])[1];  
            @endphp
            <tr>
                <td>{{$email['email']}}</td>
                @if ($isExport)
                    <td>{{($email['status']=='valid' )? 'Safe to Send':'Bounce' }}</td>
                    <td>{{$email['apiStatus']}}</td>
                    <td>5</td>
                    <td>No</td>
                    <td>Yes</td> 
                    <td>{{ $domain}}</td>  
                @endif
               
            </tr>
            
            
        @endforeach
    </tbody>
</table>