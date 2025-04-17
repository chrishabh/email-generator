<table>
    <thead>
        <tr>
            <th>EMAIL</th>
            @if ($isExport)
                {{-- <th>RESULT</th> --}}
                @if (!$isBulkUploadApi)
                    <th>REASON</th>
                @endif
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
                    
                    {{-- @if($isExport && !$isBulkUploadApi) 
                        <td>{{($email['status']=='valid' )? 'Safe to Send':'Bounce' }}</td>  --}}
                    {{-- @elseif($isExport && $isBulkUploadApi) 
                        <td>{{$email['status']}}</td>
                    @endif --}}
                    
                    @if (!$isBulkUploadApi)
                      <td>{{$email['apiStatus']}}</td>
                     @endif
                    <td>{{ $domain}}</td>  
                @endif
               
            </tr>
            
            
        @endforeach
    </tbody>
</table>