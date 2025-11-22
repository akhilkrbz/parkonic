@foreach($parkingSessions as $key => $session)

<tr>
    <td>{{ $key + 1 }}</td>
    <td>{{ $session->in_time->format('d M Y, h:i A') }}</td>
    <td>{{ $session->out_time ? $session->out_time->format('d M Y, h:i A') : 'N/A' }}</td>
    <td>{{ $session->location->name }}</td>
    <td>{{ $session->building->name }}</td>
    <td>{{ $session->entryAccessPoint->name }}</td>
    <td>{{ $session->exitAccessPoint->name ?? 'N/A' }}</td>
    <td>{{ $session->vehicleMaster->plate_code.' '.$session->vehicleMaster->plate_number.' '.$session->vehicleMaster->emirates }}</td>
    <td>{!! $session->status == 1 ? '<span style="color:green;">Active</span>' : '<span style="color:red;">Closed</span>' !!}</td>
    <td>
        @if($session->status == 2 && $session->out_time)
            @php
                $totalMinutes = $session->out_time->diffInMinutes($session->in_time)*(-1);
                $hours = intdiv($totalMinutes, 60);
                $mins = $totalMinutes % 60;
                
                $duration = '';
                if ($hours > 0) {
                    $duration .= $hours . ' ' . ($hours === 1 ? 'hr' : 'hrs');
                }
                if ($mins > 0) {
                    if ($duration !== '') {
                        $duration .= ' ';
                    }
                    $duration .= $mins . ' min';
                }
                if ($duration === '') {
                    $duration = '0 min';
                }
            @endphp
            {{ $duration }}
        @else
            N/A
        @endif
    </td>
</tr>

@endforeach

