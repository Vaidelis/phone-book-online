export interface PhoneBook {
    id: number;
    name: string;
    phone_number: string;
    user_id: number;
}

export interface PhoneBookSharing {
    [phoneBookId: number]: number[];
}
