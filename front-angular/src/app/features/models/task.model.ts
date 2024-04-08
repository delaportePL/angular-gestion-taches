export interface Task {
	id: string;
	title: string;
	description: string;
	state: string;
	user_id: string;
	points: number;
	category: string;
}