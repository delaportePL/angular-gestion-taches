import { Task } from "./task.model";

export interface Project {
	projectId: string;
	label: string;
	tasks: Task[];
}