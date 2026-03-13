package usecase

import (
	"context"
	"redis-car-departure-system/domain"
	"redis-car-departure-system/repository"
)

type VehicleUsecase interface {
	CreateVehicle(ctx context.Context, vehicle *domain.Vehicle) error
	GetVehicle(ctx context.Context, id string) (*domain.Vehicle, error)
	GetAllVehicles(ctx context.Context) ([]*domain.Vehicle, error)
	UpdateVehicleStatus(ctx context.Context, id string, status string) error
	DeleteVehicle(ctx context.Context, id string) error
	ScheduleDeparture(ctx context.Context, vehicleID string, destination string) error
}

type vehicleUsecase struct {
	vehicleRepo    repository.VehicleRepository
	departureRepo  repository.DepartureRepository
}

func NewVehicleUsecase(vehicleRepo repository.VehicleRepository, departureRepo repository.DepartureRepository) VehicleUsecase {
	return &vehicleUsecase{
		vehicleRepo:   vehicleRepo,
		departureRepo: departureRepo,
	}
}

func (u *vehicleUsecase) CreateVehicle(ctx context.Context, vehicle *domain.Vehicle) error {
	vehicle.Status = "menunggu"
	return u.vehicleRepo.Save(ctx, vehicle)
}

func (u *vehicleUsecase) GetVehicle(ctx context.Context, id string) (*domain.Vehicle, error) {
	return u.vehicleRepo.FindByID(ctx, id)
}

func (u *vehicleUsecase) GetAllVehicles(ctx context.Context) ([]*domain.Vehicle, error) {
	return u.vehicleRepo.FindAll(ctx)
}

func (u *vehicleUsecase) UpdateVehicleStatus(ctx context.Context, id string, status string) error {
	return u.vehicleRepo.UpdateStatus(ctx, id, status)
}

func (u *vehicleUsecase) DeleteVehicle(ctx context.Context, id string) error {
	return u.vehicleRepo.Delete(ctx, id)
}

func (u *vehicleUsecase) ScheduleDeparture(ctx context.Context, vehicleID string, destination string) error {
	// Update vehicle status to ready
	err := u.vehicleRepo.UpdateStatus(ctx, vehicleID, "siap_berangkat")
	if err != nil {
		return err
	}

	// Add to departure queue
	return u.departureRepo.AddToQueue(ctx, vehicleID)
}