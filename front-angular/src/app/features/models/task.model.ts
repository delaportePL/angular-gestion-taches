export interface Task {
	id: number;
	title: string;
	description: string;
	state: string;
	user_id: number;
	points: number;
	category: string;
}