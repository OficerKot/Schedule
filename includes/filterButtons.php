<div class="filterButtonsContainer">
	<label>
		Группа:
		<select id="groupFilter">
			<option value="">Все</option>
		</select>
	</label>

	<label>
		Преподаватель:
		<select id="teacherFilter">
			<option value="">Все</option>
		</select>
	</label>


	<label>
		Аудитория:
		<select id="classroomFilter">
			<option value="">Все</option>

		</select>
	</label>
</div>

<style>
.filterButtonsContainer {
	display: flex;
	gap: 12px;
	align-items: center;
}
.filterButtonsContainer select {
	padding: 4px 8px;
	font-size: 14px;
	border: 1px solid #b4b4b4;
	border-radius: 4px;
	min-width: 200px;
}
.filterButtonsContainer label {
	display: flex;
	align-items: center;
	gap: 6px;
}
</style>