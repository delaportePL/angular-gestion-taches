import { Task } from "./task.model";

export interface TaskResponse {
	message: string;
	tasks: Task[];
}