<h3>Result Details</h3>
<div class="container-fluid span12 make-grid">
<table class="table table-striped">
	<tr>
		<th>Name:</th>
				<td>
				{{ $result['fullname'] }}
				</td>
			</tr>
		<tr>
		<th>Language: </th>
				<td>
				{{ $result['langName'] }}
				</td>
			</tr>
		<tr>
			<tr>
		<th>Category:</th>
				<td>
				{{ $result['categoryName'] }}
				</td>
			</tr>
			<tr>
			<th>Question count:</th>
				<td>
				{{ $result['total_qs_count'] }}
				</td>
			</tr>
			<tr>
			<th>Number of correct answers:</th>
				<td>
				{{ $result['correct_count'] }}
				</td>
			</tr>
			<tr>
			<th>Number of wrong answers:</th>
				<td>
				{{ $result['wrong_count'] }}
				</td>
			</tr>
			<tr>
			<th>Total Marks:</th>
				<td>
				{{ $result['total_marks'] }}
				</td>
			</tr>
			@if($result['descriptive_count']>0)
			<tr>
			<th>Descriptive answer count:</th>
				<td>
				{{ $result['descriptive_count'] }}
				</td>
			</tr>
			@endif
			</table>
			<a class="btn btn-primary btnBack" href="http://localhost:8000/result/list">Back</a>
		</div>
   