<h2>📅 Weekly Activities</h2>
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Day</th>
            <th>Activity</th>
            <th>Hours Required</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $activities_stmt = $conn->prepare("SELECT day, activity, hours_required FROM weekly_activities ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')");
        $activities_stmt->execute();
        $result = $activities_stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>{$row['day']}</td><td>{$row['activity']}</td><td>{$row['hours_required']} hrs</td></tr>";
        }
        ?>
    </tbody>
</table>
