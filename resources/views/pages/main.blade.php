<h1>
Inner Page
</h1>
<table>
    <tr>
        <td>Title</td>
        <td>{{ $data->title }}</td>
    </tr>
    <tr>
        <td>Alias</td>
        <td>{{ $data->alias }}</td>
    </tr>
    <tr>
        <td>Type</td>
        <td>{{ $data->category->title }}</td>
    </tr>


</table>
{!! html_entity_decode($data->pageitem->description) !!}
