export interface Task {
	_id: any;
	idTask: string;
	projectId: string;

	title: string;
	category: string;
	description: string;
	state: string;
	points: number;
	priority: string;

	creatorUserId: string;
	assignedUserId: string;

	creationDate: Date;
	modificationDate: Date;
}